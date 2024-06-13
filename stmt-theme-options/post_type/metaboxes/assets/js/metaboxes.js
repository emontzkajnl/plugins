(function ($) {
    $(document).ready(function () {

        $('[data-vue]').each(function () {

            let $this = $(this);
            let data = JSON.parse($this.attr('data-vue'));
            new Vue({
                el: $(this)[0],
                data: function () {
                    return {
                        loading: false,
                        data: data,
                    }
                },
                mounted: function() {

                },
                methods: {
                    changeTab: function(tab) {
                        let $tab = $('#' + tab);
                        $tab.closest('.stmt_metaboxes_grid__inner').find('.stmt-to-tab').removeClass('active');
                        $tab.addClass('active');

                        let $section = $('div[data-section="' + tab + '"]');
                        $tab.closest('.stmt_metaboxes_grid__inner').find('.stmt-to-nav').removeClass('active');
                        $section.addClass('active');

                    },
                    saveSettings: function(id) {
                        var vm = this;
                        vm.loading = true;
                        this.$http.post(stmt_to_ajaxurl + '?action=stmt_save_settings&name=' + id, JSON.stringify(vm.data)).then(function(response){
                            vm.loading = false;
                        });
                    }
                }
            });

        });
    });
})(jQuery);