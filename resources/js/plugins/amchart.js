window.loadAmChart = async () => {
    const am5 = await import('@amcharts/amcharts5')
    window.am5 = am5
}
