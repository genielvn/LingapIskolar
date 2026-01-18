<div
    class="flex items-center justify-between rounded-xl border border-zinc-200 bg-white p-4 shadow-sm"
>
    <form
        method="GET"
        action="{{ route("dashboard") }}"
        class="flex w-full items-center gap-6"
    >
        <div class="flex flex-1 flex-col items-center gap-4 md:flex-row">
            <div class="block w-full md:hidden">
                <x-text-input
                    :id="'search'"
                    :icon="'bi-search'"
                    :value="request('search')"
                    :label="'Search'"
                />
            </div>
            @if (in_array("status", $filters))
                <x-select-input
                    :id="'status'"
                    :label="'Status'"
                    :value="request('status')"
                >
                    <x-option-input
                        :options="['All', 'Open', 'Assigned', 'In Progress', 'Pending User Response', 'Escalated', 'Resolved', 'Closed']"
                        :selectName="'status'"
                    />
                </x-select-input>
            @endif

            @if (in_array("category", $filters))
                <x-select-input
                    :id="'category'"
                    :label="'Category'"
                    :value="request('category')"
                >
                    <x-option-input
                        :options="['All', 'Scholarship Inquiry', 'Financial Assistance', 'Documents Submission', 'Application Status', 'Technical Support', 'General Inquiry']"
                        select-name="category"
                    />
                </x-select-input>
            @endif

            @if (in_array("priority", $filters))
                <x-select-input :id="'priority'" :label="'Priority'">
                    <x-option-input
                        :options="['All', 'Urgent', 'High', 'Medium', 'Low']"
                        select-name="priority"
                    />
                </x-select-input>
            @endif

            <div class="flex flex-col items-center gap-2 md:flex-row">
                <x-button type="submit" class="min-w-32">
                    Apply Filters
                </x-button>
                @if (request()->anyFilled(["search", "status", "category", "priority"]))
                    <a
                        href="{{ route("dashboard") }}"
                        class="flex items-center px-4 text-sm font-medium text-zinc-500 transition hover:text-red-800"
                    >
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>
