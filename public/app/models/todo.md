Rearange
---

-- #1 --

[ok] app\controllers\cms\Course -> [*] app\controllers\Course

[ok] app\controllers\admin\Users_admin -> [delete]

[ok] app\controllers\Admin -> [delete]

[ok] app\controllers\admin\Course_admin -> [*] app\controllers\Course_admin

[ok] app\models\admin\User_model -> [*] app\models\User_model

[ok] app\models\admin\Privilege_model -> [merge_to] -> [**] app\models\User_model

[ok] app\models\admin\History_model -> [*] app\models\History_model

[ok] app\models\cms\Page_model -> [delete]

[ok] app\models\cms\Media_model -> [delete]

[ok] app\models\cms\Course_model -> [*] app\models\Course_model


[*] = move


-- #2 --

[ok] app\controllers\Course -> [rename] app\controllers\Cms

[ok] app\controllers\Course_admin -> [rename] app\controllers\CmsAdmin

[ok] app\models\Course_model -> [rename] app\models\Cms_model

[ok] app\extends\Send_mail -> [rename] SendMail_service



Upgrades (??)
---


A1

A2 : includes A1

A3 : includes A1, A2

A4 : includes A1, A2, A3


A1  100
A2  200
A3  350
A4  500

B1  100
B2  200
B3  400
B4  800


:1 -> 0%
:2 -> 15%
:3 -> 20%




A1 -> A2
A1 -> A3
A1 -> A4

A2 -> A3
A2 -> A4

A3 -> A4

A1 < A2 < A3 < A4



B1 -> B2
B1 -> B3
B1 -> B4

B2 -> B3
B2 -> B4

B3 -> B4


A1+B1

A2+B2

A3+B3


A1+B1 -> A2+B2
A1+B1 -> A3+B3
A1+B1 -> A4+B4

A2+B2 -> A3+B3
A2+B2 -> A4+B4

A3+B3 -> A4+B4



UPGRADE PRICE = upper_priv_price - own_priv_price

buy 1 item : -0%
buy 2 items : -10%
buy 3 items : -15%
