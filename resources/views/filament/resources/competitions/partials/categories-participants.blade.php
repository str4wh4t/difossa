@php
    use App\Filament\Resources\CompetitionRegistrations\CompetitionRegistrationResource;
    use App\Models\CompetitionCategory;

    /** @var \App\Models\Competition $competition */
    /** @var \App\Filament\Resources\Competitions\Pages\ViewCompetition $page */

    $categories = CompetitionCategory::query()->orderBy('name')->get();
@endphp

<div class="fi-w-full">
    @forelse ($categories as $category)
        @php
            $registrations = $competition->registrations
                ->where('competition_category_id', $category->id)
                ->values();
            $winners = $page->getCategoryWinners($competition, $category);
            $registerAction = $page->getCategoryRegisterAction($competition, $category);
            $canManageWinners = $page->canManageWinners();
            $canViewParticipants = $page->canViewParticipantsList();
        @endphp

        <div style="margin-bottom: {{ $loop->last ? '0' : '1rem' }} !important;">
            <x-filament::section :description="filled($category->description) ? $category->description : null">
                <x-slot name="heading">
                    {{ $category->name }}
                </x-slot>

                @if ($registerAction['visible'])
                    <x-slot name="afterHeader">
                        @if ($registerAction['disabled'])
                            <x-filament::button
                                color="gray"
                                size="sm"
                                disabled
                                :icon="\Filament\Support\Icons\Heroicon::OutlinedCheckCircle"
                            >
                                {{ $registerAction['label'] }}
                            </x-filament::button>
                        @else
                            <x-filament::button
                                tag="a"
                                :href="$registerAction['url']"
                                size="sm"
                                :icon="\Filament\Support\Icons\Heroicon::OutlinedPlus"
                            >
                                {{ $registerAction['label'] }}
                            </x-filament::button>
                        @endif
                    </x-slot>
                @endif

                @if ($canViewParticipants)
                    @if ($registrations->isEmpty())
                        <x-filament::callout
                            color="info"
                            :icon="\Filament\Support\Icons\Heroicon::OutlinedUserGroup"
                            heading="No participants yet"
                            description="No participants registered in this category yet."
                        />
                    @else
                        <div class="fi-in-table-repeatable">
                            <table>
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Participant</th>
                                        <th>Email</th>
                                        <th>Affiliation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($registrations as $registration)
                                        @php
                                            $user = $registration->user;
                                            $participantName = $user?->full_name ?: $user?->name ?: 'Unknown';
                                        @endphp

                                        <tr>
                                            <td>
                                                <div class="fi-in-entry">
                                                    <dt class="fi-in-entry-label">No</dt>
                                                    <dd class="fi-in-entry-content">
                                                        {{ $loop->iteration }}
                                                    </dd>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fi-in-entry">
                                                    <dt class="fi-in-entry-label">Participant</dt>
                                                    <dd class="fi-in-entry-content">
                                                        {{ $participantName }}
                                                    </dd>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fi-in-entry">
                                                    <dt class="fi-in-entry-label">Email</dt>
                                                    <dd class="fi-in-entry-content">
                                                        {{ $user?->email ?? '-' }}
                                                    </dd>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fi-in-entry">
                                                    <dt class="fi-in-entry-label">Affiliation</dt>
                                                    <dd class="fi-in-entry-content">
                                                        {{ $user?->affiliation ?? '-' }}
                                                    </dd>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif

                @if ($competition->isFinished())
                    <div class="fi-competition-winners-section">
                        <x-filament::section
                            :description="$canManageWinners
                                ? 'Assign winners from registrations in this category.'
                                : 'Winners announced for this category.'"
                        >
                            <x-slot name="heading">
                                Winners
                            </x-slot>

                            @if ($canManageWinners && $winners->isNotEmpty())
                                <x-slot name="afterHeader">
                                    <x-filament::button
                                        size="sm"
                                        :icon="\Filament\Support\Icons\Heroicon::OutlinedTrophy"
                                        wire:click="mountAction('assignCategoryWinner', { categoryId: {{ $category->id }} })"
                                    >
                                        Assign winner
                                    </x-filament::button>
                                </x-slot>
                            @endif

                            @if ($winners->isEmpty())
                                <x-filament::callout
                                    color="gray"
                                    :icon="\Filament\Support\Icons\Heroicon::OutlinedTrophy"
                                    heading="No winners assigned"
                                    :description="$canManageWinners
                                        ? 'Select a registration below to assign the first winner for this category.'
                                        : 'No winners have been assigned for this category yet.'"
                                >
                                    @if ($canManageWinners)
                                        <x-slot name="footer">
                                            <x-filament::button
                                                size="sm"
                                                :icon="\Filament\Support\Icons\Heroicon::OutlinedTrophy"
                                                wire:click="mountAction('assignCategoryWinner', { categoryId: {{ $category->id }} })"
                                            >
                                                Assign winner
                                            </x-filament::button>
                                        </x-slot>
                                    @endif
                                </x-filament::callout>
                            @else
                                <div class="fi-in-table-repeatable">
                                    <table>
                                        <thead>
                                            <tr>
                                                @if ($canManageWinners)
                                                    <th class="fi-in-table-repeatable-empty-header-cell"></th>
                                                @endif
                                                <th>Rank</th>
                                                <th>Participant</th>
                                                <th>Article</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($winners as $winner)
                                                @php
                                                    $registration = $winner->registration;
                                                    $user = $registration?->user;
                                                    $participantName = $user?->full_name ?: $user?->name ?: 'Unknown';
                                                @endphp

                                                <tr>
                                                    @if ($canManageWinners)
                                                        <td>
                                                            <div class="fi-competition-winner-actions">
                                                                <x-filament::icon-button
                                                                    size="sm"
                                                                    color="gray"
                                                                    :icon="\Filament\Support\Icons\Heroicon::OutlinedPencilSquare"
                                                                    label="Edit"
                                                                    wire:click="mountAction('editCategoryWinner', { winnerId: {{ $winner->id }}, categoryId: {{ $category->id }} })"
                                                                />
                                                                <x-filament::icon-button
                                                                    size="sm"
                                                                    color="danger"
                                                                    :icon="\Filament\Support\Icons\Heroicon::OutlinedTrash"
                                                                    label="Remove"
                                                                    wire:click="mountAction('deleteCategoryWinner', { winnerId: {{ $winner->id }}, categoryId: {{ $category->id }} })"
                                                                />
                                                            </div>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <div class="fi-in-entry">
                                                            <dt class="fi-in-entry-label">Rank</dt>
                                                            <dd class="fi-in-entry-content">
                                                                {{ $winner->rank }}
                                                            </dd>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fi-in-entry">
                                                            <dt class="fi-in-entry-label">Participant</dt>
                                                            <dd class="fi-in-entry-content">
                                                                {{ $participantName }}
                                                            </dd>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fi-in-entry">
                                                            <dt class="fi-in-entry-label">Article</dt>
                                                            <dd class="fi-in-entry-content">
                                                                {{ $registration?->article_title ?? '-' }}
                                                            </dd>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </x-filament::section>
                    </div>
                @endif
            </x-filament::section>
        </div>
    @empty
        <x-filament::callout
            color="warning"
            :icon="\Filament\Support\Icons\Heroicon::OutlinedTag"
            heading="No categories"
            description="No competition categories have been created yet."
        />
    @endforelse
</div>
