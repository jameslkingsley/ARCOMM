Vue.filter('fromnow', value => {
    return moment(value).fromNow();
});

Vue.filter('date', (value, format = 'Do MMMM YYYY') => {
    return moment(value).format(format);
});
