const search = document.getElementById("search");

if (search) {
    search.addEventListener("input", function () {
        const value = this.value.toLowerCase().trim();

        document.querySelectorAll("#studentTable tbody tr").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
}
