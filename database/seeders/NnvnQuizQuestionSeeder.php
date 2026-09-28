<?php

namespace Database\Seeders;

use App\MiniApps\NnvnApisGo\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class NnvnQuizQuestionSeeder extends Seeder
{
    public function run(): void
    {
        $questions = [
            [
                'question_vi' => 'Novo Nordisk được thành lập vào năm nào?',
                'question_en' => 'What year was Novo Nordisk founded?',
                'options' => ['1920', '1923', '1930', '1935'],
                'options_en' => ['1920', '1923', '1930', '1935'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Trụ sở chính của Novo Nordisk đặt tại quốc gia nào?',
                'question_en' => 'In which country is Novo Nordisk headquartered?',
                'options' => ['Thụy Điển', 'Đan Mạch', 'Na Uy', 'Phần Lan'],
                'options_en' => ['Sweden', 'Denmark', 'Norway', 'Finland'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Novo Nordisk nổi tiếng nhất trong lĩnh vực nào?',
                'question_en' => 'Novo Nordisk is most famous for which field?',
                'options' => ['Tim mạch', 'Tiểu đường', 'Ung thư', 'Thần kinh'],
                'options_en' => ['Cardiology', 'Diabetes', 'Oncology', 'Neurology'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Apis trong tiếng Latin có nghĩa là gì?',
                'question_en' => 'What does "Apis" mean in Latin?',
                'options' => ['Kiến', 'Ong', 'Bướm', 'Chuồn chuồn'],
                'options_en' => ['Ant', 'Bee', 'Butterfly', 'Dragonfly'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Loài ong mật thuộc chi nào?',
                'question_en' => 'What genus do honey bees belong to?',
                'options' => ['Bombus', 'Apis', 'Vespa', 'Osmia'],
                'options_en' => ['Bombus', 'Apis', 'Vespa', 'Osmia'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Một con ong thợ có bao nhiêu đôi cánh?',
                'question_en' => 'How many pairs of wings does a worker bee have?',
                'options' => ['1', '2', '3', '4'],
                'options_en' => ['1', '2', '3', '4'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Ong mật giao tiếp bằng cách nào?',
                'question_en' => 'How do honey bees communicate?',
                'options' => ['Tiếng kêu', 'Điệu nhảy', 'Màu sắc', 'Mùi hương'],
                'options_en' => ['Sound', 'Dance', 'Color', 'Scent'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Insulin được phát hiện vào năm nào?',
                'question_en' => 'In what year was insulin discovered?',
                'options' => ['1918', '1921', '1925', '1930'],
                'options_en' => ['1918', '1921', '1925', '1930'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Tiểu đường type 1 là do cơ quan nào bị tổn thương?',
                'question_en' => 'Type 1 diabetes is caused by damage to which organ?',
                'options' => ['Gan', 'Tụy', 'Thận', 'Tim'],
                'options_en' => ['Liver', 'Pancreas', 'Kidney', 'Heart'],
                'correct_index' => 1,
            ],
            [
                'question_vi' => 'Logo của Novo Nordisk có hình con vật gì?',
                'question_en' => 'What animal is in the Novo Nordisk logo?',
                'options' => ['Sư tử', 'Bò Apis', 'Đại bàng', 'Không có con vật'],
                'options_en' => ['Lion', 'Apis Bull', 'Eagle', 'No animal'],
                'correct_index' => 1,
            ],
        ];

        foreach ($questions as $question) {
            QuizQuestion::updateOrCreate(
                ['question_vi' => $question['question_vi']],
                array_merge($question, ['is_active' => true])
            );
        }
    }
}
