// Textos de ag-Grid en español
const textosAgGridEs = {
    // Estados generales
    loadingOoo: 'Cargando...',
    noRowsToShow: 'No hay registros para mostrar',

    // Filtros
    filterOoo: 'Filtrar...',
    searchOoo: 'Buscar...',
    applyFilter: 'Aplicar',
    clearFilter: 'Limpiar',
    resetFilter: 'Restablecer',
    cancelFilter: 'Cancelar',
    filterTitle: 'Filtro',
    andCondition: 'Y',
    orCondition: 'O',

    // Operadores de texto
    contains: 'Contiene',
    notContains: 'No contiene',
    equals: 'Igual a',
    notEqual: 'Distinto de',
    startsWith: 'Empieza con',
    endsWith: 'Termina con',
    blank: 'Vacío',
    notBlank: 'No vacío',
    blanks: '(Vacíos)',
    noMatches: 'Sin coincidencias',

    // Operadores numéricos y de fecha
    greaterThan: 'Mayor que',
    greaterThanOrEqual: 'Mayor o igual que',
    lessThan: 'Menor que',
    lessThanOrEqual: 'Menor o igual que',
    inRange: 'Entre',
    inRangeStart: 'Desde',
    inRangeEnd: 'Hasta',

    // Selección
    selectAll: '(Seleccionar todo)',
    selectAllSearchResults: '(Seleccionar todos los resultados)',

    // Paginación
    page: 'Página',
    more: 'Más',
    to: 'a',
    of: 'de',
    next: 'Siguiente',
    last: 'Última',
    first: 'Primera',
    previous: 'Anterior',
    nextPage: 'Página siguiente',
    lastPage: 'Última página',
    firstPage: 'Primera página',
    previousPage: 'Página anterior',
    pageSizeSelectorLabel: 'Tamaño de página:',

    // Accesibilidad
    ariaFilterColumn: 'Presiona CTRL + ENTER para abrir el filtro',
    ariaFilterValue: 'Valor del filtro',
    ariaFilterFromValue: 'Filtrar desde el valor',
    ariaFilterToValue: 'Filtrar hasta el valor',
    ariaFilterMenuOpen: 'Abrir menú de filtro',
    ariaSearchFilterValues: 'Buscar valores del filtro'
};

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
        rowData: datos,
        localeText: textosAgGridEs
    });
}
