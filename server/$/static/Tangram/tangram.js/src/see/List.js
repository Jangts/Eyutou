/*!
 * tangram.js framework source code
 *
 * http://www.yangram.net/tangram.js/
 *
 * Date: 2017-04-06
 */
;
tangram.block([
    '$_/util/bool',
    '$_/dom/Elements'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        doc = global.document,
        location = global.location,
        $ = _.dom.select;



    declare('see.List', {
        _init: function(elem) {
            this.Element = _.util.bool.isStr(elem) ? _.dom.query.byId(elem) : elem;
            if (_.util.bool.isEl(this.Element)) {
                this.render();
            }
        },
        render: function() {
            if (_.dom.hasClass(this.Element, 'withthumb')) {
                var itemWidth, mediaWidth, bodyWidth;
                $('.tangram-see .articlelist.withthumb>.list-item', this.Element).each(function() {
                    if (_.dom.hasClass(this, 'top-bottom')) {
                        return this;
                    }
                    itemWidth = $(this).innerWidth();
                    mediaWidth = Math.ceil($('.list-figure,.list-image, img', this).outerWidth(true));
                    bodyWidth = itemWidth - mediaWidth - 1;
                    $('.list-body', this).sub(0).width(bodyWidth);
                    // console.log(itemWidth, mediaWidth, $('.list-body', this).sub(0));

                });
                return this;
            }
        }
    });

    _.extend(_.see.List, {
        auto: function() {
            var List = this;
            $('.tangram-see .articlelist[data-ic-auto]').each(function() {
                if (($(this).data('icAuto') != 'false') && ($(this).data('icRendered') != 'articlelist')) {
                    $(this).data('icRendered', 'articlelist');
                    new List(this);
                }
            });
        }
    });
});