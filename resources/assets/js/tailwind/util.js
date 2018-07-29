const _ = require('lodash')

module.exports = function ({ addUtilities, addComponents, e, prefix, config }) {
    addUtilities({
        '.pin-t-full': { top: '100%' },
        '.pin-b-full': { bottom: '100%' },
        '.pin-l-full': { left: '100%' },
        '.pin-r-full': { right: '100%' },
    })

    _.map(config('margin', []), (value, name) => {
        let classes = {
            [`.first\\:m-${name}`]: { '&:first-of-type': { 'margin': value } },
            [`.last\\:m-${name}`]: { '&:last-of-type': { 'margin': value } },
        }

        for (let side of ['top', 'bottom', 'left', 'right']) {
            classes[`.first\\:m${side.substr(0, 1)}-${name}`] = {
                '&:first-of-type': { [`margin-${side}`]: value }
            }

            classes[`.last\\:m${side.substr(0, 1)}-${name}`] = {
                '&:last-of-type': { [`margin-${side}`]: value }
            }
        }

        addUtilities(classes)
    })
}
