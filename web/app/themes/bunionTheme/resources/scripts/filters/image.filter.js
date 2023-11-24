/**
 * @see {@link https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/#blocks-registerblocktype}
 */
export const hook = 'blocks.registerBlockType';

/**
 * Filter handle
 */
export const name = 'sage/image';

/**
 * Filter callback
 *
 * @param {object} settings
 * @param {string} name
 * @returns modified settings
 */
export function callback(settings, name) {
  if (name !== 'core/image') return settings;

  return {
    ...settings,
    styles: [
      {label: 'Outline', name: 'outline'},
      {label: 'Rounded', name: 'rounded'},
      {label: 'Blog Image', name: 'blog'},
    ],
  };
}
