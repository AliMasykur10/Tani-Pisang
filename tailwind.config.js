import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",

    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                bg: "#F7F7F5",
                surface: "#FFFFFF",
                primary: {
                    DEFAULT: "#2F5233",
                    tint: "#E3EBE2",
                },
                ink: {
                    DEFAULT: "#1C1F1D",
                    muted: "#6B7268",
                },
                line: "#E5E5E1",
                success: "#2F7A4D",
                danger: "#B3413A",
                warn: "#B8863A",
            },
        },
    },

    plugins: [forms],
};
