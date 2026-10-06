function convertirTablaAAGGrid(idTabla, idGrid) {
    const filas = document.querySelectorAll(`#${idTabla} tbody tr`);

    const encabezados = [...document.querySelectorAll(`#${idTabla} thead th`)].map((th, i) => ({
        field: 'col' + i,
        headerName: th.textContent.trim() || 'Acciones',
        cellRenderer: params => params.value,
        width: i === 0 ? 100 : undefined,
        flex: i === 0 ? undefined : 1,
        filter: true
    }));

    const datos = [...filas].map(tr => {
        const obj = {};
        tr.querySelectorAll('td').forEach((td, i) => {
            obj['col' + i] = td.innerHTML.trim(); // innerHTML conserva el link "Ver"
        });
        return obj;
    });

    agGrid.createGrid(document.getElementById(idGrid), {
        columnDefs: encabezados,
        rowData: datos
    });
}