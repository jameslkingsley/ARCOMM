function enableMentions(elem, type, column) {
    $(elem).atwho({
        at: "@",
        limit: 5,
        insertTpl: '${atwho-at}${name}',
        callbacks: {
            remoteFilter: function(query, callback) {
                if (query.length <= 1) return;

                $.getJSON("/api/mentions/" + type, {
                    q: query,
                    c: column
                }, function(data) {
                    callback(data);
                });
            }
        }
    });

    $(elem).on('inserted.atwho', function(jevent, li, bevent) {
        var name = li.text().trim();
        var field = $(elem).parents('form').find('#mentions-list');
        var list = field.val().split(',');
        if (list.includes(name)) return;
        list.push(name);
        field.val(list.toString());
    });
}
