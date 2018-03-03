<template>
    <div class="grid" :class="classes" :style="styles">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: {
            // CSS Properties
            gap: { type: String, default: null },
            rowGap: { type: String, default: null },
            autoRows: { type: String, default: null },
            columnGap: { type: String, default: null },
            autoColumns: { type: String, default: null },
            templateRows: { type: String, default: null },
            templateColumns: { type: String, default: null },

            // Helpers
            singles: { type: Boolean, default: null },
            quarters: { type: Boolean, default: null },
            thirds: { type: Boolean, default: null },
            doubles: { type: Boolean, default: null },
            card: { type: Boolean, default: null }
        },

        computed: {
            styles() {
                let rules = {};

                for (let key in this.$props) {
                    let helperMethod = `helper${this.capitalizeFirstLetter(key)}`;

                    if (
                        typeof this[helperMethod] === 'function' &&
                        this.$props[key] !== null
                    ) {
                        let helper = this[helperMethod]();

                        if (Array.isArray(helper)) {
                            for (let attr of helper) {
                                rules[attr.property] = attr.value;
                            }
                        } else {
                            rules[helper.property] = helper.value;
                        }

                        continue;
                    }

                    if (this.$props[key] !== null) {
                        rules[`grid-${key}`] = this.$props[key];
                    }
                }

                return rules;
            },

            classes() {
                return {
                    card: this.$props.card !== null
                };
            }
        },

        methods: {
            capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },

            helperDoubles() {
                return {
                    property: 'grid-template-columns',
                    value: '0.5fr 0.5fr'
                };
            },

            helperThirds() {
                return {
                    property: 'grid-template-columns',
                    value: '0.333333fr 0.333333fr 0.333333fr'
                };
            },

            helperSingles() {
                return {
                    property: 'grid-template-columns',
                    value: '1fr'
                };
            }
        }
    };
</script>

<style scoped>
    .grid {
        display: grid;
    }
</style>
