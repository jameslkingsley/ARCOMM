<template>
    <div :style="styles">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: [
            // CSS Properties
            'gap',
            'area',
            'row-gap',
            'row-end',
            'row-start',
            'auto-rows',
            'column-gap',
            'column-end',
            'column-start',
            'auto-columns',
            'template-rows',
            'template-columns'
        ],

        computed: {
            styles() {
                let rules = {};

                for (let key in this.$props) {
                    let helperMethod = `helper${this.capitalizeFirstLetter(key)}`;

                    if (
                        typeof this[helperMethod] === 'function' &&
                        typeof this.$props[key] != 'undefined'
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

                    if (typeof this.$props[key] != 'undefined') {
                        rules[`grid-${key}`] = this.$props[key];
                    }
                }

                return rules;
            }
        },

        methods: {
            capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        }
    };
</script>
