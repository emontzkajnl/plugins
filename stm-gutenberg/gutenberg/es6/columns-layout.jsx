registerBlockType ('stm-gutenberg/columns-layout', {
    title: 'STM Columns With Right Sidebar',
    icon: 'universal-access-alt',
    category: 'stm_blocks',
    edit: function (props) {

        var TEMPLATE = [ [ 'core/columns', { className: 'row'}, [
            [ 'core/column', { className: 'col-md-8'} ],
            [ 'core/column', { className: 'col-md-4 stmt-sticky-sidebar'} ],
        ] ] ];
        return (
            <InnerBlocks template={ TEMPLATE } templateLock={ false } />
        );
    },

    save: function (props) {
        return (<InnerBlocks.Content />);
    },
});