// Custom Chart.js plugin: draw each slice value inside pie/doughnut charts,
// so counts are visible without hovering. No external dependency required.
const inSlicePieLabels = {
    id: "inSlicePieLabels",
    afterDatasetsDraw(chart) {
        const { ctx } = chart;

        chart.data.datasets.forEach((dataset, datasetIndex) => {
            const meta = chart.getDatasetMeta(datasetIndex);

            if (!["pie", "doughnut"].includes(meta.type)) {
                return;
            }

            meta.data.forEach((element, index) => {
                const value = dataset.data[index];

                if (!value || element.hidden) {
                    return;
                }

                const { x, y } = element.tooltipPosition();

                ctx.save();
                ctx.font = "700 25px ui-sans-serif, system-ui, sans-serif";
                ctx.fillStyle = "#ffffff";
                ctx.textAlign = "center";
                ctx.textBaseline = "middle";
                ctx.shadowColor = "rgba(0, 0, 0, 0.55)";
                ctx.shadowBlur = 4;
                ctx.fillText(String(value), x, y);
                ctx.restore();
            });
        });
    },
};

window.filamentChartJsPlugins ??= [];
window.filamentChartJsPlugins.push(inSlicePieLabels);
