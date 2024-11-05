<?php

namespace App\Console\Commands;

use App\Enums\TicketTypeEnum;
use App\Models\TicketType;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Throwable;

/**
 * @uses FillTicketTypesCommand
 */
class FillTicketTypesCommand extends Command
{
    protected $signature = 'ticket-types:fill';
    protected $description = 'Fill ticket_types table';

    public function handle(): int
    {
        try {
            $ticketTypes = TicketTypeEnum::getValues();

            $existingTicketTypes = TicketType::query()
                ->pluck('name')
                ->toArray();

            if (count($ticketTypes) !== count($existingTicketTypes) || array_diff($ticketTypes, $existingTicketTypes) || array_diff($existingTicketTypes, $ticketTypes)) {
                $this->updateOrCreateTicketTypes($ticketTypes);
                $this->deleteObsoleteTicketTypes($ticketTypes, $existingTicketTypes);

                $this->info('Table ticket_types updated successfully!');
            } else {
                $this->info('No changes needed in the ticket_types table.');
            }

            return CommandAlias::SUCCESS;
        } catch (Throwable $e) {

            $this->error('[Table ticket_types fill error]: ' . $e->getMessage());
            return CommandAlias::FAILURE;
        }
    }

    protected function updateOrCreateTicketTypes(array $ticketTypes): void
    {
        foreach ($ticketTypes as $ticketType) {
            TicketType::query()->updateOrCreate(
                ['name' => $ticketType],
                ['description' => 'Description for ' . ucfirst($ticketType) . ' ticket type.']
            );
        }
    }

    protected function deleteObsoleteTicketTypes(array $ticketTypes, array $existingTicketTypes): void
    {
        $obsoleteTypes = array_diff($existingTicketTypes, $ticketTypes);

        if (!empty($obsoleteTypes)) {
            TicketType::query()->whereIn('name', $obsoleteTypes)->delete();
        }
    }
}
