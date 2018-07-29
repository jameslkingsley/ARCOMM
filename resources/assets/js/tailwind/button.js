const _ = require('lodash')
const color = require('color-js')

module.exports = function ({ base, colors }) {
    return function ({ addUtilities, addComponents, e, prefix, config }) {
        const baseButton = Object.assign({
            'outline': '0 !important',
            'transition': 'all .15s ease',
            'text-transform': 'uppercase',
            'color': config('colors.black'),
            'font-size': config('textSizes.sm'),
            'box-shadow': config('shadows.button'),
            'font-weight': config('fontWeights.medium'),
            'letter-spacing': config('tracking.wide'),
            'background-color': config('colors.white'),
            'border-radius': config('borderRadius.default'),
            'padding': `${config('padding.3')} ${config('padding.5')}`,

            '&:not([disabled]):not(.btn-loading):not(.btn-text)': {
                '&:hover': {
                    'color': config('colors.grey-darkest'),
                    'box-shadow': config('shadows.button-hover'),
                },

                '&:active': {
                    'color': config('colors.grey-dark'),
                    'box-shadow': config('shadows.button-active'),
                },

                // '&:focus': {
                //     'box-shadow': `${config('shadows.outline')}`,
                // }
            },

            '&[disabled], &.disabled, &.btn-loading': {
                'opacity': 0.5,
                'cursor': 'default'
            },

            '&.btn-large': {
                'font-size': config('textSizes.sm'),
                'padding': `${config('padding.4')} ${config('padding.6')}`,
            },

            '&.btn-small': {
                'font-size': config('textSizes.xxs'),
                'padding': `${config('padding.2')} ${config('padding.3')}`,
            },

            '&.btn-text': {
                'box-shadow': 'none',
                'background-color': 'transparent',
            },

            '&.p-0': {
                'padding': '0 !important'
            }
        }, base)

        let variants = []

        for (let name in colors) {
            let normal = colors[name][0]
            let active = colors[name][1]
            let light = color(config(`colors.${normal}`)).setAlpha(0.5).toRGB()

            variants.push([
                {
                    [`.btn.btn-${name}`]: {
                        'color': 'white',
                        'background-color': config(`colors.${normal}`),

                        '&:not([disabled]):not(.btn-loading)': {
                            '&:active': {
                                'color': 'white',
                                'background-color': config(`colors.${active}`),
                            },

                            '&:hover': {
                                'color': 'white !important'
                            },

                            // '&:focus': {
                            //     'box-shadow': `0 0 0 3px ${light} !important`,
                            // }
                        }
                    }
                }
            ])
        }

        addComponents([
            { '.btn': baseButton },
            ...variants
        ])
    }
}
