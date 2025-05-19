// Mapa centrado en Catalunya
const map = L.map('mapa').setView([41.8, 1.6], 8);

// Capa base de OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Cargar comarcas desde el archivo local JSON
fetch('/data/divisions-administratives-v2r1-comarques-100000-20250101.json')
    .then(response => response.json())
    .then(data => {
        L.geoJSON(data, {
            style: {
                color: "#2E86AB",
                weight: 1,
                fillColor: "#AED6F1",
                fillOpacity: 0.3
            },
            onEachFeature: (feature, layer) => {
                const nom = feature.properties.NOMCOMAR;
                const cap = feature.properties.CAPCOMAR;
                layer.bindPopup(`<strong>${nom}</strong><br>Capital: ${cap}`);
            }
        }).addTo(map);
    })
    .catch(error => console.error('Error cargando GeoJSON:', error));
