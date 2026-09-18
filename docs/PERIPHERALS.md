# Periféricos Vendi POS

Vendi usa una capa de periféricos desacoplada.

## Transportes
- Web Serial: básculas/puertos seriales compatibles desde Chrome.
- WebHID: dispositivos HID compatibles.
- Keyboard: lectores que emulan teclado.
- Vendi Bridge: fallback para hardware Windows/legacy.

## Básculas
El perfil podrá definir baud rate, bits, paridad, unidad, expresión de lectura, estabilidad, tara y decimales. Nunca se debe asumir que todas las básculas emiten el mismo protocolo.

## Seguridad
El navegador solicita autorización explícita para dispositivos compatibles. Vendi no debe intentar evadir permisos del navegador. El Bridge deberá autenticar las peticiones locales y aceptar únicamente orígenes Vendi autorizados.
