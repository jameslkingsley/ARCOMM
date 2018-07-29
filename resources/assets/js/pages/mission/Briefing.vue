<template>
    <div class="p-6">
        <button
            :key="index"
            class="px-6 py-4 mb-8 uppercase font-bold"
            v-for="(faction, index) in factions"
            @click="activeFaction = faction.name"
            :class="{
                'text-primary': activeFaction == faction.name,
                'text-grey-lighter': activeFaction != faction.name,
            }">{{ faction.name }}
        </button>

        <div class="px-6" v-for="(section, index) in faction.sections" :key="index">
            <h3 class="mb-2 font-bold">{{ startCase(index) }}</h3>

            <p
                v-for="(line, index) in section"
                :key="index"
                v-html="line"
                :class="{
                    'mb-0': startsWith(line, '-'),
                    'mb-8': !startsWith(line, '-') || index === section.length - 1,
                }"></p>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                activeFaction: null
            }
        },

        computed: {
            mission() {
                return this.$store.state.mission.viewing
            },

            factions() {
                let filled = []

                for (let f in this.mission.cfg.briefing) {
                    // Always skip game master briefing
                    if (f === 'game_master') continue

                    let filtered = {}
                    let faction = this.mission.cfg.briefing[f]

                    for (let s in faction) {
                        if (faction[s].length) {
                            filtered[s] = faction[s]
                        }
                    }

                    if (Object.keys(filtered).length) {
                        filled.push({
                            name: f.toLowerCase(),
                            sections: filtered
                        })
                    }
                }

                return filled
            },

            faction() {
                return _.find(this.factions, ['name', this.activeFaction])
            }
        },

        methods: {
            startsWith(string, target) {
                return _.startsWith(string, target)
            },

            startCase(string) {
                return _.startCase(string)
            }
        },

        created() {
            this.activeFaction = this.factions[0].name
        }
    }
</script>
