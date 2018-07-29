<template>
    <svgicon
        :name="name"
        :width="width ? width : size"
        :height="height ? height : size"
        :color="tailwindColor">
    </svgicon>
</template>

<script>
    import { colors } from '../../../../../tailwind'

    export default {
        props: {
            name: String,
            color: String,
            size: { type: String, default: null },
            width: { type: String, default: null },
            height: { type: String, default: null },
        },

        computed: {
            tailwindColor() {
                return colors[this.color]
            }
        }
    }
</script>
