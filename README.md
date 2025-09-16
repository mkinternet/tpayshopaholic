# Moduł  tpay dla Lovata Shopaholic

Wtyczka integrująca bramkę tpay z modułem [Lovata Shopaholic](https://github.com/oc-shopaholic/oc-shopaholic-plugin) dla [Octobercms](https://github.com/octobercms/october) w wersji 2.x.

## Konfiguracja

Ustawienia -> metody płatności -> dodaj płatność
- nazwa: Tpay
- kod: tpay
- bramka: wybierz: Tpay

Wciśnij "Stwórz"

W trybie edycji w zakładce bramka
- ustawić walutę na PLN
- przypisać statusy płatności
- zaznaczyć:  Wyślij zapytanie do bramki płatności podczas tworzenia zamówienia
- podać id sprzedawcy, hasło sprzedawcy, klucz api, hasło api

W ustawieniach przesyłki zaznaczyć dla których ma się wyświetlać płatność Tpay.

Utworzyć podstrony:

order-complete-page.html

```
title = "Zamówienie zostało złożone poprawnie"
url = "/order-complete-page"
layout = "subpage"
is_hidden = 0
robot_index = "index"
robot_follow = "follow"
```

order-fail-page.html

```
title = "Zamówienie nie powiodło się"
url = "/order-fail-page"
layout = "subpage"
is_hidden = 0
robot_index = "index"
robot_follow = "follow"
```

---


Wykonane przez MK Internet.
