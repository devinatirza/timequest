module.exports = {
    content: [
      './resources/**/*.blade.php',
      './resources/**/*.js',
      './resources/**/*.vue',
    ],
    theme: {
      extend: {
        colors: {
          'navbar-bg': '#1C1814',
          'logo-gold': '#D4AF37',
          'menu-text': '#F5F5F5',
          'text-brown': '#1E140A',
          'heading-white': '#FFFFFF',
          'subheading-gold': '#E6C770',
          'button-bg': '#D4AF37',
          'button-text': '#1C1814',
          'error-text' : '#EE6B6E'
        },
        fontFamily: {
          'display': ['Cinzel', 'serif'],
          'serif': ['Cormorant', 'serif'],
          'sans': ['Raleway', 'sans-serif'],
        },
      },
    plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/typography'),
      require('@tailwindcss/aspect-ratio'),
    ],
  }
}