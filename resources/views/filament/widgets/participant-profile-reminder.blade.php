<x-filament-widgets::widget class="fi-participant-profile-reminder-widget">
    <x-filament::callout
        color="warning"
        :icon="\Filament\Support\Icons\Heroicon::OutlinedExclamationTriangle"
        heading="Complete your profile"
        description="Add your full name and affiliation before you can register for a competition."
    >
        <x-slot name="footer">
            <x-filament::button
                tag="a"
                :href="$this->getEditProfileUrl()"
                size="sm"
                :icon="\Filament\Support\Icons\Heroicon::OutlinedUserCircle"
            >
                Complete profile
            </x-filament::button>
        </x-slot>
    </x-filament::callout>
</x-filament-widgets::widget>
