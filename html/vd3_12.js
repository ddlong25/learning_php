
document.addEventListener('DOMContentLoaded', () => {
    const textEl = document.getElementById('text');
    const resultEl = document.getElementById('result');
    const getBtn = document.querySelector('button[onclick="getStyle()"]');
    const setBtn = document.querySelector('button[onclick="setStyle()"]');

    if (setBtn) setBtn.disabled = true;

    window.getStyle = function () {
        if (!textEl || !resultEl) return;
        const cs = getComputedStyle(textEl);
        const color = cs.color.replace(/,\s*/g, ',');
        const size = cs.fontSize;
        const weight = cs.fontWeight;

        resultEl.style.whiteSpace = 'pre-line';

        resultEl.textContent =
            `Màu chữ :${color}\n` +
            `Kích thước chữ :${size}\n` +
            `Độ đậm: ${weight}`;

        // bật nút gán style sau khi đã lấy style
        if (setBtn) setBtn.disabled = false;
    };

        window.setStyle = function () {
        if (!textEl || !setBtn || setBtn.disabled) return;
        textEl.style.color = 'red';
        textEl.style.backgroundColor = 'yellow';
    };
});
