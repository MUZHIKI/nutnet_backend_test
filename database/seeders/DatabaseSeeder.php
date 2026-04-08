<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('password'),
            ]
        );

        $albums = [
            [
                'title' => 'Группа крови',
                'artist' => 'Кино',
                'description' => 'Один из главных альбомов советского рока. В этих песнях соединились холодная мелодика, точные тексты Виктора Цоя и ощущение времени конца 1980-х.',
                'cover_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music125/v4/a2/f7/13/a2f713de-4af3-9e21-da3f-c73d747cd847/cover.jpg/600x600bb.jpg',
            ],
            [
                'title' => 'Звезда по имени Солнце',
                'artist' => 'Кино',
                'description' => 'Поздний и очень цельный альбом группы, где минимализм звучания сочетается с мрачной и пронзительной лирикой. Для многих слушателей это одна из вершин дискографии Кино.',
                'cover_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music124/v4/77/7b/42/777b4246-02ba-e1ea-eaee-c574a3350ca1/cover.jpg/600x600bb.jpg',
            ],
            [
                'title' => 'Крылья',
                'artist' => 'Наутилус Помпилиус',
                'description' => 'Пластинка, на которой фирменная меланхолия Наутилуса сочетается с мощными песнями о свободе, вере и внутреннем выборе. Альбом закрепил статус группы как важнейшего явления русского рока.',
                'cover_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music49/v4/8c/b5/96/8cb59693-5702-ae97-ece7-4a283b20b1e6/cover.jpg/600x600bb.jpg',
            ],
            [
                'title' => 'Это всё',
                'artist' => 'ДДТ',
                'description' => 'Один из самых узнаваемых альбомов ДДТ, где гражданская интонация соседствует с личными и очень человеческими песнями. Работа хорошо показывает масштаб авторского взгляда Юрия Шевчука.',
                'cover_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music221/v4/fd/6a/fa/fd6afabb-8e23-7150-af6b-641c3a69b5ac/9_001_DDT-Eto_vsyo.jpg/600x600bb.jpg',
            ],
            [
                'title' => 'Морская',
                'artist' => 'Мумий Тролль',
                'description' => 'Альбом, с которого для широкой публики началась новая поп-рок эпоха конца 1990-х. Он выделяется лёгкостью, необычной подачей текста и очень узнаваемым стилем Ильи Лагутенко.',
                'cover_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music124/v4/9f/fc/2c/9ffc2c68-7a6a-5a2e-174e-fe6f8d597cf8/morskaya.jpg/600x600bb.jpg',
            ],
            [
                'title' => '25-й кадр',
                'artist' => 'Сплин',
                'description' => 'Один из самых известных альбомов Сплина начала 2000-х. В нём слышны и фирменная меланхолия группы, и сильные мелодии, благодаря которым пластинка стала важной частью русского рока того времени.',
                'cover_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music124/v4/db/4a/c5/db4ac58b-d8b3-ab9a-f77a-79810509c2e6/859719935307_cover.jpg/600x600bb.jpg',
            ],
            [
                'title' => 'Rumours',
                'artist' => 'Fleetwood Mac',
                'description' => 'Классический альбом 1970-х, ставший эталоном мягкого рок-звучания. За внешней лёгкостью здесь скрывается напряжённая личная история участников группы, что делает песни особенно живыми.',
                'cover_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music124/v4/4d/13/ba/4d13bac3-d3d5-7581-2c74-034219eadf2b/081227970949.jpg/600x600bb.jpg',
            ],
            [
                'title' => 'The Dark Side of the Moon',
                'artist' => 'Pink Floyd',
                'description' => 'Одна из самых влиятельных рок-пластинок в истории. Альбом строится как цельное путешествие по темам времени, страха, давления и человеческой уязвимости.',
                'cover_url' => 'https://upload.wikimedia.org/wikipedia/en/3/3b/Dark_Side_of_the_Moon.png',
            ],
            [
                'title' => 'Nevermind',
                'artist' => 'Nirvana',
                'description' => 'Альбом, который сделал гранж глобальным явлением. Грубая энергия, сильные мелодии и нервный голос Курта Кобейна превратили пластинку в символ поколения.',
                'cover_url' => 'https://upload.wikimedia.org/wikipedia/en/b/b7/NirvanaNevermindalbumcover.jpg',
            ],
            [
                'title' => 'OK Computer',
                'artist' => 'Radiohead',
                'description' => 'Холодный, тревожный и невероятно современный даже спустя годы альбом о технологиях, отчуждении и внутренней тревоге человека в сложном мире.',
                'cover_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music116/v4/07/60/ba/0760ba0f-148c-b18f-d0ff-169ee96f3af5/634904078164.png/600x600bb.jpg',
            ],
            [
                'title' => 'Abbey Road',
                'artist' => 'The Beatles',
                'description' => 'Поздняя работа The Beatles, в которой слышны и зрелость группы, и её удивительная лёгкость. Пластинка известна не только песнями, но и своим историческим значением для всей поп-музыки.',
                'cover_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music211/v4/48/53/43/485343e3-dd6a-0034-faec-f4b6403f8108/13UMGIM63890.rgb.jpg/600x600bb.jpg',
            ],
            [
                'title' => 'Back in Black',
                'artist' => 'AC/DC',
                'description' => 'Мощный и прямолинейный хард-рок альбом, ставший одной из самых продаваемых пластинок в истории. Он звучит уверенно, громко и до сих пор остаётся образцом жанра.',
                'cover_url' => 'https://is1-ssl.mzstatic.com/image/thumb/Music115/v4/1e/14/58/1e145814-281a-58e0-3ab1-145f5d1af421/886443673441.jpg/600x600bb.jpg',
            ],
        ];

        Album::query()->delete();
        Album::query()->insert(array_map(function (array $album) {
            return array_merge($album, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $albums));
    }
}
