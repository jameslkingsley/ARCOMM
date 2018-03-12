<template>
    <grid thirds gap="2rem">
        <div :style="boxStyles" :class="boxClasses">
            <span class="inline-block w-full text-lg font-medium mb-1">{{ modeFull }} on {{ mission.map.name }}</span>
            <span class="inline-block w-full mb-3">{{ dateTime | date }} &mdash; {{ dateTime | date('HH:mm') }}</span>
            <span class="inline-block w-full">{{ mission.summary }}</span>
        </div>

        <div :style="boxStyles" :class="boxClasses">Box</div>

        <div :style="boxStyles" :class="boxClasses">Box</div>
    </grid>
</template>

<script>
    export default {
        props: {
            mission: Object
        },

        data() {
            return {
                boxClasses: {
                    'bg-white': true,
                    'shadow-lg': true,
                    'rounded': true,
                    'p-6': true
                },

                boxStyles: {
                    'height': 0,
                    'padding-bottom': 'calc(100% - 1.5rem)'
                }
            };
        },

        computed: {
            modeFull() {
                return {
                    'coop': 'Cooperative',
                    'preop': 'Pre-Operation',
                    'adversarial': 'Adversarial',
                }[this.mission.mode];
            },

            dateTime() {
                return moment().set({
                    year: this.mission.sqm.mission.intel.year,
                    month: this.mission.sqm.mission.intel.month,
                    day: this.mission.sqm.mission.intel.day,
                    hour: this.mission.sqm.mission.intel.hour,
                    minute: this.mission.sqm.mission.intel.minute,
                });
            }
        },

        methods: {
            //
        }
    }
</script>
