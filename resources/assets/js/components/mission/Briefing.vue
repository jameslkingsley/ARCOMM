<template>
    <grid template-columns="0.15fr 0.85fr">
        <div class="pt-4">
            <button
                :key="index"
                v-text="faction.name"
                v-for="(faction, index) in factions"
                @click.prevent="activeFaction = faction"
                :class="{
                    'py-2': true,
                    'w-full': true,
                    'uppercase': true,
                    'text-left': true,
                    'no-underline': true,
                    'inline-block': true,
                    'font-semibold': true,
                    'cursor-pointer': true,
                    'hover:opacity-100': true,
                    'opacity-50': activeFaction.name !== faction.name,
                    'opacity-100': activeFaction.name === faction.name
                }"></button>
        </div>

        <grid class="bg-white rounded shadow-lg p-6" auto-rows gap="2rem">
            <div v-for="(section, index) in activeFaction.sections" :key="index">
                <h4 class="mb-2">{{ startCase(index) }}</h4>

                <p
                    v-for="(line, index) in section"
                    :key="index"
                    v-html="line"
                    :class="{
                        'mb-0': startsWith(line, '-')
                    }"></p>
            </div>
        </grid>
    </grid>
</template>

<script>
    export default {
        props: {
            mission: Object
        },

        data() {
            return {
                activeFaction: null
            };
        },

        computed: {
            factions() {
                let filled = [];

                for (let f in this.mission.cfg.briefing) {
                    // Always skip game master briefing
                    if (f === 'game_master') continue;

                    let filtered = {};
                    let faction = this.mission.cfg.briefing[f];

                    for (let s in faction) {
                        if (faction[s].length) {
                            filtered[s] = faction[s];
                        }
                    }

                    if (Object.keys(filtered).length) {
                        filled.push({
                            name: f,
                            sections: filtered
                        });
                    }
                }

                return filled;
            }
        },

        methods: {
            startsWith(string, target) {
                return _.startsWith(string, target);
            },

            startCase(string) {
                return _.startCase(string);
            }
        },

        created() {
            this.activeFaction = this.factions[0];
        }
    }
</script>
