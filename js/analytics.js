// analytics.js - carrega GA4 e envia eventos de clique
(function(){
  // ID de medição do GA4
  var GA_MEASUREMENT_ID = 'G-BRXRGBF2S6';

  // Carrega gtag.js de forma assíncrona
  var s = document.createElement('script');
  s.async = true;
  s.src = 'https://www.googletagmanager.com/gtag/js?id=' + GA_MEASUREMENT_ID;
  document.head.appendChild(s);

  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  window.gtag = gtag;
  gtag('js', new Date());
  gtag('config', GA_MEASUREMENT_ID, { 'anonymize_ip': false });

  // Função utilitária para enviar eventos
  function sendEvent(name, params){
    if(window.gtag){
      gtag('event', name, params || {});
    }
  }

  // Rastrear cliques em links externos (target=_blank ou href externo)
  function isExternalLink(anchor){
    try{
      return anchor.hostname && anchor.hostname !== window.location.hostname;
    }catch(e){
      return false;
    }
  }

  document.addEventListener('click', function(ev){
    var el = ev.target;
    while(el && el.tagName !== 'A') el = el.parentElement;
    if(!el) return;

    var href = el.getAttribute('href') || '';

    // Links para páginas de destino internas (pasta /destinos/)
    if(href.indexOf('destinos/') !== -1){
      sendEvent('click_destino', { 'destination': href });
      return;
    }

    // Links externos
    if(isExternalLink(el) || el.target === '_blank'){
      sendEvent('click_external', { 'label': href });
      return;
    }
  }, false);

})();
