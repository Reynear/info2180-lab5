document.addEventListener('DOMContentLoaded', () => {
    let lookupBtn = document.getElementById("lookup");
    let input = document.getElementById("country");
    let result = document.getElementById("result");

    lookupBtn.addEventListener('click', async () => {
        const country = input.value.trim();
        const response = await fetch(`world.php?country=${encodeURIComponent(country)}`);
        const html = await response.text();
        result.innerHTML = html;
    });
});
