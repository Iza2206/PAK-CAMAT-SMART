/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                primary: "#1D4ED8",
                danger: "#DC2626",
                success: "#16A34A",
                warning: "#D97706",
            },
        },
    },
    plugins: [],
};
