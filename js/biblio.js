function weather(donnee) {
// console.log(donnees);

    let markersLayer = new L.LayerGroup();
    map.addLayer(markersLayer);
    let searchCtrl = new L.Control.Search({
        position: 'topleft',
        layer: markersLayer,
        initial: false,
        zoom:12,
        marker: false,
        textPlaceholder: 'Recherche ...'
    });
    map.addControl(searchCtrl);

    donnee.forEach((el) => {
        if (el.place['country'] === 'FR') {
            let lat = el.place.location['1'];
            let lon = el.place.location['0'];

            let loc = [lat, lon];

            let marker;
            marker = L.marker([lat, lon]);
            marker.addTo(map);

            let city = Object.entries(el.place)[4];
            let ville = null;

             if (city === undefined) {
                 ville = 'Non défini';
             }else{
                 ville = city[1];
             }
            let street = Object.entries(el.place)[5];

            let mesure = Object.entries(el.measures)[0][1].res;

            let temp = Object.entries(mesure)[0][1][0];
            let humididate = Object.entries(mesure)[0][1][1];

            let pressure = Object.entries(el.measures)[1][1].res;
            let pasc = Object.entries(pressure)[0][1][0];

            marker = new L.Marker(new L.latLng(loc), {title: ville});
            marker.bindPopup(`<h2>${ville}</h2><p>Température : ${temp}°C</p><p>Taux d'humidité : ${humididate}%</p><p>Pression : ${pasc} Pa</p>`).openPopup();
            markersLayer.addLayer(marker);
        }
    });
}