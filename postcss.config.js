import tailwindcss from '@tailwindcss/postcss';
import autoprefixer from 'autoprefixer';
import purgecss from '@fullhuman/postcss-purgecss';

const production = process.env.NODE_ENV === 'production';
const purgecssPlugin =
  typeof purgecss === 'function' ? purgecss : purgecss?.default ?? purgecss;

const purgePlugin = purgecssPlugin({
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.{vue,js,ts}',
    './resources/css/**/*.css',
    './storage/framework/views/*.php',
    './app/View/**/*.php',
    './app/Http/**/*.php',
  ],
  defaultExtractor: (content) => content.match(/[\w-/:!%#.@]+(?<!:)/g) ?? [],
  safelist: {
    standard: ['html', 'body', 'bi'],
    deep: [/^bi-/],
  },
});

export default {
  plugins: [tailwindcss(), autoprefixer(), ...(production ? [purgePlugin] : [])],
};
