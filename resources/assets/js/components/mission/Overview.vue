<template>
    <grid thirds gap="2rem">
        <div :style="boxStyles" :class="boxClasses">
            <span class="inline-block w-full text-lg font-medium mb-1">{{ modeFull }} on {{ mission.map.name }}</span>
            <span class="inline-block w-full mb-3">{{ dateTime | date }} &mdash; {{ dateTime | date('HH:mm') }}</span>
            <span class="inline-block w-full">{{ mission.summary }}</span>
        </div>

        <div :style="boxStyles" :class="boxClasses">
            <span class="inline-block w-full text-lg font-medium mb-6">{{ weatherText }}</span>
            <img :src="weatherImage" class="block mx-auto" :style="{
                'width': '60%',
                'max-width': 'none'
            }" />
        </div>

        <div :style="boxStyles" :class="boxClasses">
            <span class="inline-block w-full text-lg font-medium mb-1">Published {{ mission.created_at | fromnow }}</span>

            <span v-if="mission.last_played" class="inline-block w-full">
                Last played {{ mission.last_played | fromnow }}
            </span>

            <span v-else class="inline-block w-full">
                Mission has not been played in an operation yet! Soon&trade;
            </span>
        </div>
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
            },

            fog() {
                return this.computeLessThan(this.mission.sqm.mission.intel.startFog || 0, {
                    'NONE': 0.0,
                    'Light Fog': 0.1,
                    'Medium Fog': 0.3,
                    'Heavy Fog': 0.5,
                    'Extreme Fog': 1.0,
                });
            },

            overcast() {
                return this.computeLessThan(this.mission.sqm.mission.intel.startWeather || 0, {
                    'Clear Skies': 0.1,
                    'Partly Cloudy': 0.3,
                    'Heavy Clouds': 0.6,
                    'Stormy': 1.0
                });
            },

            rain() {
                let startRain = this.mission.sqm.mission.intel.startRain || 0;
                let forecastRain = this.mission.sqm.mission.intel.forecastRain || 0;
                let diff = forecastRain - startRain;

                return this.computeLessThan(diff, {
                    'NONE': 0,
                    'Slight Drizzle': 0.2,
                    'Drizzle': 0.4,
                    'Rain': 0.6,
                    'Showers': 1
                });
            },

            weatherText() {
                return `${this.overcast}${this.fog ? `, ${this.fog}` : ''}${this.rain ? `, ${this.rain}` : ''}`;
            },

            weatherImage() {
                return '/images/weather/' + ({
                    'Clear Skies': 'clear',
                    'Partly Cloudy': 'partly sunny',
                    'Heavy Clouds': 'partly cloudy',
                    'Stormy': 'cloudy',
                    'Clear Skies, Slight Drizzle': 'slight drizzle',
                    'Clear Skies, Drizzle': 'light rain',
                    'Clear Skies, Rain': 'rain',
                    'Clear Skies, Showers': 'showers',
                    'Partly Cloudy, Slight Drizzle': 'slight drizzle',
                    'Partly Cloudy, Drizzle': 'light rain',
                    'Partly Cloudy, Rain': 'rain',
                    'Partly Cloudy, Showers': 'showers',
                    'Heavy Clouds, Slight Drizzle': 'slight drizzle',
                    'Heavy Clouds, Drizzle': 'light rain',
                    'Heavy Clouds, Rain': 'rain',
                    'Heavy Clouds, Showers': 'showers',
                    'Stormy, Slight Drizzle': 'slight drizzle',
                    'Stormy, Drizzle': 'light rain',
                    'Stormy, Rain': 'rain',
                    'Stormy, Showers': 'showers'
                })[`${this.overcast}${this.rain ? `, ${this.rain}` : ''}`] + '.png';
            }
        },

        methods: {
            computeLessThan(value, options) {
                for (let option in options) {
                    if (value <= options[option]) {
                        return option === 'NONE' ? '' : option;
                    }
                }
            }
        }
    }
</script>
