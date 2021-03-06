/*!
 * tanguage framework source code
 *
 * class forms/SimpleEditor
 * 
 * Date: 2015-09-04
 */
;
tang.init().block([
    '$_/util/bool',
    '$_/dom/'
], function(pandora, root, imports, undefined) {
    var _ = pandora,

        console = root.console,
        query = _.dom.sizzle || _.dom.selector,

        regMethod = pandora.storage.get(new _.Identifier('EDITOR_REG_M').toString()),
        regCommand = pandora.storage.get(new _.Identifier('EDITOR_REG_CMD').toString()),
        regCreater = pandora.storage.get(new _.Identifier('EDITOR_REG_C').toString()),
        regDialog = pandora.storage.get(new _.Identifier('EDITOR_REG_D').toString());

    regMethod('createLink', function(val) {
        return this.execCommand('createlink', val);
    });

    regCommand('createlink', function(val) {
        if (val && _.util.bool.isUrl(val.url)) {
            var url = 'http://temp.';
            url += new _.Identifier();
            url += '.com';
            if (this.selection.getRange().type === 'Caret') {
                this.execCommand('insert', val.url);
            }
            this.selection.getRange().execCommand('createlink', url);
            this.selection.collapseRange();
            var a = query('a[href="' + url + '"]');
            _.each(a, function() {
                this.href = val.url;
                if (val.isNew) {
                    this.target = '_blank';
                }
            });
            this.selection.restoreSelection();
            this.onchange();
        }
        return this;
    });

    regCreater('createlink', function() {
        var html = '<dialog class="se-dialog">';
        // html += '<span class="se-title">Insert link</span>';
        html += '<div class="se-url">';
        html += '<label><i>Enter URL</i><input type="text" class="se-input createlink" placeholder="http://www.yangram.com/tanguage/" /></label>';
        html += '</div>';
        html += '<div class="se-check">';
        html += '<label><input type="checkbox" class="se-checkbox" checked="checked">Open in new tab</label>';
        html += '</div>';
        html += '<div class="se-btns">';
        html += '<button type="button" class="data-se-cmd" data-se-cmd="createlink">OK</button>';
        html += '</div>';
        html += '</dialog>';
        return html;
    });

    regDialog('createlink', function(btn) {
        var dialog = _.dom.getClosestParent(btn, 'dialog');
        var input = query('.se-url .se-input', dialog)[0];
        var checkbox = query('.se-check .se-checkbox', dialog)[0]
        if (input && input.value != '') {
            return {
                url: input.value,
                isNew: checkbox && checkbox.checked
            }
        }
        return null;
    });
});