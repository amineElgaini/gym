function animateValue(element, end) {
    let start = 0;
    let duration = 800;
    let step = Math.ceil(end / (duration / 16));
    
    let interval = setInterval(() => {
        start += step;
        if (start >= end) {
            element.textContent = end;
            clearInterval(interval);
        } else {
            element.textContent = start;
        }
    }, 16);
}
