// Zentrale Skript-Datei für eigene JS-Logik
// Bootstrap-spezifische oder eigene Features können hier später ergänzt werden.

// Dynamisches Filtern der Abteilungen nach Bereichswahl
window.addEventListener('DOMContentLoaded', function () {
  var bereich = document.querySelector('select[name="bereich_id"]');
  var abteilung = document.querySelector('select[name="abteilung_id"]');
  if (!(bereich && abteilung)) return;

  // Originaloptionen merken
  var alle = Array.from(abteilung.options).map(function(opt){
    return {v: opt.value, t: opt.text, bid: opt.getAttribute('data-bid') };
  });

  // alle Abteilungsoptionen einblenden entsprechend Bereichswahl
  bereich.addEventListener('change', function() {
    var val = bereich.value;
    abteilung.innerHTML = '';
    abteilung.add(new Option('bitte wählen...',''));
    alle.forEach(function(o) {
      if (!o.v || o.bid === val) {
        abteilung.add(new Option(o.t, o.v));
      }
    });
    abteilung.disabled = (abteilung.options.length <= 1);
  });

  // Initial: abteilung auf disabled, außer vorbefüllt beim Edit
  if (!abteilung.value) {
    abteilung.disabled = true;
  }
});
