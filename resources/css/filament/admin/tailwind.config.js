import preset from '../../../../vendor/filament/filament/tailwind.config.preset';
const colors = require('tailwindcss/colors');
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './app/Livewire/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/livewire/**/*.blade.php',
        './resources/views/vendor/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            keyframes: {
                rainbow: {
                    '0%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                    '100%': { backgroundPosition: '0% 50%' },
                },
            },
            animation: {
                rainbow: 'rainbow 5s linear infinite',
            },
            backgroundImage: {
                'rainbow-gradient':
                    'linear-gradient(45deg, #ff0000, #ff4500, #ff8c00, #ffd700, #32cd32, #00fa9a, #00ffff, #1e90ff, #4169e1, #8a2be2, #ff00ff, #ff1493)',
                'conic-gradient':
                    'conic-gradient(#ff0000, #ff4500, #ff8c00, #ffd700, #32cd32, #00fa9a, #00ffff, #1e90ff, #4169e1, #8a2be2, #ff00ff, #ff1493)',
            },
            colors: {
                gray: colors.slate,
                primary: {
                    50: "#EFF8FF",
                    100: "#D8EEFE",
                    200: "#ACDEFD",
                    300: "#74CEFC",
                    400: "#2DBDF4",
                    500: "#27AADB",
                    600: "#1C85AD",
                    700: "#136381",
                    800: "#094358",
                    900: "#032532",
                    950: "#021822"
                },
                secondary: {
                    50: "#D8FFF1",
                    100: "#A8FFE2",
                    200: "#12F6C6",
                    300: "#10E3B6",
                    400: "#0DCEA5",
                    500: "#0BBA95",
                    600: "#069275",
                    700: "#046E57",
                    800: "#024939",
                    900: "#012A1F",
                    950: "#001A12"
                },
                danger: colors.red,
                success: colors.emerald,
                warning: colors.yellow,
                info: colors.cyan,
            },
        },
    },
};
