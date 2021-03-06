<script type="text/javascript">
	<?php
	ob_start();
	include STMT_TO_DIR . '/post_type/metaboxes/components/socials.php';
	$template = preg_replace( "/\r|\n/", "", addslashes(ob_get_clean()));
	?>

    Vue.component('stmt-socials', {
        props: ['stored_faq'],
        data: function () {
            return {
                faq: {},
            }
        },
        mounted: function() {
            if(this.stored_faq) {
                this.faq = JSON.parse(this.stored_faq);
            }
        },
        template: '<?php echo $template; ?>',
        methods: {
            changed: function(key){
                if(this.faq[key] == '') {
                    Vue.delete(this.faq, key);
                } else {
                    Vue.set(this.faq, key, this.faq[key]);
                }
            }
        },
        watch: {
            faq: {
                handler: function () {
                    this.$emit('get-faq', JSON.stringify(this.faq));
                },
                deep: true
            }
        }
    })
</script>