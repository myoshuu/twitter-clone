/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        primary: "#1DA1F2",
        primaryDarker: "#0D8BDB",
        primaryLight: "#51bbfc",
        grey: "#EBEEF0",
        darkGrey: "#747474",
      },
    },
  },
  plugins: [require("daisyui")],
};
