<script type="text/javascript">
	<?php
	ob_start();
	include STMT_TO_DIR . '/post_type/metaboxes/components/multiselect.php';
	$template = preg_replace( "/\r|\n/", "", addslashes(ob_get_clean()));
	?>

    Vue.component('multiselect', VueMultiselect.Multiselect);
    Vue.component('stmt-multiselect', {
        props: ['options', 'selected_options'],
        data: function () {
            return {
                multiselect: [],
            }
        },
        mounted: function() {
            if(this.selected_options) {
                this.multiselect = JSON.parse(this.selected_options);
            }
        },
        template: '<?php echo $template; ?>',
        methods: {

        },
        watch: {
            multiselect: {
                handler: function () {
                    this.$emit('get-selects', JSON.stringify(this.multiselect));
                },
                deep: true
            }
        }
    })
</script>