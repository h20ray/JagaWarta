(function () {
  'use strict';
  if (typeof wp === 'undefined' || !wp.element || !wp.hooks || !wp.blockEditor || !wp.components || !wp.data) return;

  var el = wp.element.createElement;
  var Fragment = wp.element.Fragment;
  var useSelect = wp.data.useSelect;
  var addFilter = wp.hooks.addFilter;
  var InspectorControls = wp.blockEditor.InspectorControls;
  var SelectControl = wp.components.SelectControl;

  addFilter('editor.BlockEdit', 'jagawarta/section-grid-category', function (BlockEdit) {
    return function (props) {
      if (props.name !== 'jagawarta/section-grid') {
        return el(BlockEdit, props);
      }
      var categories = useSelect(function (select) {
        return select('core').getEntityRecords('taxonomy', 'category', { per_page: -1 }) || [];
      }, []);
      var options = [{ value: '', label: '— Select category —' }].concat(
        (categories || []).map(function (c) {
          return { value: c.slug, label: c.name };
        })
      );
      return el(
        Fragment,
        {},
        el(BlockEdit, props),
        el(
          InspectorControls,
          {},
          el(SelectControl, {
            label: 'Category',
            value: props.attributes.categorySlug || '',
            options: options,
            onChange: function (val) {
              props.setAttributes({ categorySlug: val || '' });
            },
          })
        )
      );
    };
  });
})();
