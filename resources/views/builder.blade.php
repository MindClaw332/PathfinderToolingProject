@vite(['resources/css/app.css', 'resources/js/app.js'])

<div class="bg-primary text-white h-full w-full">
    <!-- selection bar -->
    <div class="p-2">
        <div class="flex flex-row bg-secondary w-full p-2 border border-accent rounded-lg justify-between">
            <div>show/hide creatures/hazards</div>
            <div>randomize complete</div>
            <div>randomize partially</div>
            <div>clear</div>
            <div>export</div>
            <div>import</div>
            <div>create creature</div>
        </div>
    </div>
    <div class="flex flex-row">
        <!-- creature/hazard picker -->
        <div class="flex flex-col w-1/3 p-2 gap-4">
            <!-- creatures -->
            <div class="bg-secondary border border-accent rounded-lg">
                <!-- filter -->
                <div class="p-2">
                    <div class="w-full p-1 bg-tertiary rounded-lg">
                        filter
                    </div>
                </div>
                <!-- list creatures -->
                <div class="divide-y-1 divide-y divide-tertiary">
                    <div class="p-2">creature</div>
                    <div class="p-2">creature</div>
                    <div class="p-2">creature</div>
                    <div class="p-2">creature</div>
                </div>
            </div>
            <!-- hazards -->
            <div class="bg-secondary border border-accent rounded-lg">
                <!-- filter -->
                <div class="p-2">
                    <div class="w-full p-1 bg-tertiary rounded-lg">
                        filter
                    </div>
                </div>
                <!-- list hazard -->
                <div class="divide-y-1 divide-y divide-tertiary">
                    <div class="p-2">hazard</div>
                    <div class="p-2">hazard</div>
                    <div class="p-2">hazard</div>
                    <div class="p-2">hazard</div>
                </div>
            </div>
        </div>
        <!-- selected content -->
        <div>
            @yield('content')
        </div>
    </div>
</div>