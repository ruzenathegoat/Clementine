/** Design tokens sourced verbatim from design.txt (DESIGN.md contract). Do not add colors/values not defined there. */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#000000',
        secondary: '#FFFFFF',
        background: '#FFFFFF',
        surface: '#F3F4F6',
        'text-primary': '#000000',
        'text-inverse': '#FFFFFF',
        'text-secondary': '#6B7280',
        border: '#000000',
      },
      transitionTimingFunction: {
        'mechanical': 'cubic-bezier(0.83, 0, 0.17, 1)',
      },
      borderRadius: {
        none: '0px',
        DEFAULT: '0px',
        sm: '0px',
        md: '0px',
        lg: '0px',
        xl: '0px',
        full: '0px',
      },
      boxShadow: {
        none: 'none',
      },
      spacing: {
        xs: '4px',
        sm: '8px',
        md: '16px',
        lg: '24px',
        xl: '32px',
        '2xl': '48px',
        '3xl': '80px',
      },
      fontFamily: {
        // h1/h2 typography from design.txt uses Anton/Impact
        display: ['Anton', 'Impact', 'sans-serif'],
        // body-md typography from design.txt uses JetBrains Mono
        mono: ['"JetBrains Mono"', 'monospace'],
      },
      fontSize: {
        // h1 exact spec from design.txt
        h1: ['4.5rem', { lineHeight: '1', fontWeight: '800', letterSpacing: '-0.02em' }],
        // h2 exact spec from design.txt
        h2: ['3rem', { lineHeight: '1.1', fontWeight: '800' }],
        // body-md exact spec from design.txt
        'body-md': ['0.875rem', { lineHeight: '1.5', fontWeight: '400' }],
        // Fluid typography for massive hero headers
        'hero-lg': ['clamp(4rem, 10vw, 11rem)', { lineHeight: '0.85', fontWeight: '800', letterSpacing: '-0.02em' }],
        'hero-md': ['clamp(3rem, 7vw, 8rem)', { lineHeight: '0.85', fontWeight: '800', letterSpacing: '-0.02em' }],
      },
    },
  },
  plugins: [],
};