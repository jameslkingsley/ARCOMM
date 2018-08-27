Vue.filter('fromnow', value => {
    return moment(value).fromNow()
})

Vue.filter('date', (value, format = 'Do MMMM YYYY') => {
    return moment(value).format(format)
})

Vue.filter('capitalize', value => {
    return _.capitalize(value)
})

Vue.directive('click-outside', {
    bind: function (el, binding, vnode) {
        el.event = function (event) {
            if (!(el == event.target || el.contains(event.target))) {
                vnode.context[binding.expression](event)
            }
        }

        document.body.addEventListener('click', el.event)
    },

    unbind: function (el) {
        document.body.removeEventListener('click', el.event)
    }
})
