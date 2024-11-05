<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketType;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class OrderService
{
    /**
     * @throws Exception
     */
    public function createOrder(array $data): Model|Builder
    {
        $barcode = $this->attemptBooking($data);
        $approved = $this->isApproved($barcode);

        if (!$approved) {
            throw new Exception(trans('order.unknown_error'));
        }

        return $this->finalizeOrder(array_merge($data, ['barcode' => $barcode]));
    }

    /**
     * @throws Exception
     */
    private function finalizeOrder(array $data): Model|Builder
    {
        $assignedTickets = $this->assignTicketTypeIds($data['tickets']);
        $equalPrice = $this->calculateEqualPrice($assignedTickets);

        $order = Order::query()->create([
            'event_id' => $data['event_id'],
            'event_date' => $data['event_date'],
            'user_id' => $data['user_id'],
            'equal_price' => $equalPrice,
            'barcode' => $data['barcode']
        ]);

        /** @var Order $order */
        $this->createTickets($data, $order);

        return $order->load(['tickets.ticketType']);
    }

    /**
     * @throws Exception
     */
    private function attemptBooking(array $bookingData): string
    {
        $bookingData['barcode'] = $this->generateUniqueBarcode();

        $response = $this->sendBookingRequest($bookingData);

        if (isset($response['error']) && $response['error'] === trans('order.barcode_already_exists')) {
            throw new Exception($response['error']);
        }

        return $bookingData['barcode'];
    }

    /**
     * @throws Exception
     */
    private function isApproved(string $barcode): bool
    {
        $approvalResponse = $this->sendApprovalRequest($barcode);

        if (isset($approvalResponse['error'])) {
            throw new Exception($approvalResponse['error']);
        }

        return true;
    }

    private function createTickets(array $data, Order $order): void
    {
        foreach ($data['tickets'] as $ticket) {
            for ($i = 0; $i < $ticket['quantity']; $i++) {
                Ticket::query()->create([
                    'order_id' => $order->id,
                    'ticket_type_id' => $ticket['ticket_type_id'],
                    'price' => $ticket['price'],
                    'barcode' => $this->generateUniqueBarcode(),
                ]);
            }
        }
    }

    private function calculateEqualPrice(array $tickets): int|float
    {
        $equalPrice = 0;
        foreach ($tickets as $ticketData) {
            $equalPrice += $ticketData['price'] * $ticketData['quantity'];
        }

        return $equalPrice;
    }

    /**
     * @param array $tickets
     * @return JsonResponse|array
     * @throws Exception
     */
    private function assignTicketTypeIds(array &$tickets): JsonResponse|array
    {
        $ticketTypes = TicketType::query()
            ->pluck('id', 'name')
            ->toArray();

        foreach ($tickets as &$ticketData) {
            if (!isset($ticketTypes[$ticketData['ticket_type']])) {
                throw new Exception(trans('order.invalid_ticket_type') . ': ' . $ticketData['ticket_type']);
            }

            $ticketData['ticket_type_id'] = $ticketTypes[$ticketData['ticket_type']];
        }

        return $tickets;
    }

    private function generateUniqueBarcode(): string
    {
        do {
            $barcode = str_pad(rand(0, 999999999999), 12, '0', STR_PAD_LEFT);

            $exists = Ticket::query()->where('barcode', $barcode)->exists();
        } while ($exists);

        return $barcode;
    }

    private function sendBookingRequest(array $data): mixed
    {
        Http::fake([
            Order::BOOK_URL => function () {
                return rand(0, 1)
                    ? Http::response(['message' => trans('order.message.success')])
                    : Http::response(['error' => trans('order.barcode_already_exists')], 400);
            },
        ]);

        $response = Http::post(Order::BOOK_URL, $data);

        return $response->json();
    }

    private function sendApprovalRequest(string $barcode): mixed
    {
        Http::fake([
            Order::APPROVE_URL => function () {
                $responses = [
                    ['message' => trans('order.message.approved')],
                    ['error' => trans('order.event_cancelled')],
                    ['error' => trans('order.no_tickets')],
                    ['error' => trans('order.no_seats')],
                    ['error' => trans('order.fan_removed')],
                ];

                return Http::response($responses[array_rand($responses)]);
            },
        ]);

        $response = Http::post(Order::APPROVE_URL, ['barcode' => $barcode]);

        return $response->json();
    }
}
