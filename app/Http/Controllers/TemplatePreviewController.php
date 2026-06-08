<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TemplatePreviewController extends Controller
{
    public function preview(Template $template)
    {
        // Ensure the theme view exists
        if (!view()->exists("themes.{$template->theme_identifier}.index")) {
            abort(404, 'Theme view not found.');
        }

        // Generate mock data based on the schema
        $contentData = [];
        if (is_array($template->fields_schema)) {
            foreach ($template->fields_schema as $field) {
                $name = $field['name'];
                $type = $field['type'] ?? 'text';
                
                if ($type === 'image') {
                    // Use a beautiful Unsplash placeholder
                    $contentData[$name] = 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070&auto=format&fit=crop';
                } elseif ($type === 'gallery') {
                    $contentData[$name] = [
                        'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=1000&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1520854221256-17451cc331bf?q=80&w=1000&auto=format&fit=crop',
                        'https://images.unsplash.com/photo-1469334031218-e382a71b716b?q=80&w=1000&auto=format&fit=crop',
                    ];
                } elseif ($type === 'textarea') {
                    $nameLower = strtolower($name);
                    if (str_contains($nameLower, 'story')) {
                        $contentData[$name] = "بدأت قصتنا في يوم جميل، حيث التقينا صدفة وتبادلنا الابتسامات. ومنذ تلك اللحظة، علمنا أن أرواحنا تآلفت لتكمل معاً رحلة الحياة.";
                    } elseif (str_contains($nameLower, 'closing')) {
                        $contentData[$name] = "حضوركم يزيد من بهجتنا ويضيء ليلتنا، ننتظركم بشوق!";
                    } else {
                        $contentData[$name] = "بكل الحب والود، نتشرف بدعوتكم لمشاركتنا فرحة العمر وتناول طعام العشاء.";
                    }
                } elseif ($type === 'date') {
                    $contentData[$name] = Carbon::now()->addDays(14)->format('Y-m-d');
                } else {
                    $nameLower = strtolower($name);
                    if (str_contains($nameLower, 'groom')) {
                        $contentData[$name] = "أحمد";
                    } elseif (str_contains($nameLower, 'bride')) {
                        $contentData[$name] = "سارة";
                    } elseif (str_contains($nameLower, 'venue_name')) {
                        $contentData[$name] = "قاعة الماسة الكبرى";
                    } elseif (str_contains($nameLower, 'venue_location') || str_contains($nameLower, 'address')) {
                        $contentData[$name] = "شارع الملك فهد، الرياض";
                    } else {
                        $contentData[$name] = "نص تجريبي";
                    }
                }
            }
        }

        // Create a transient Event instance (not saved to DB)
        $mockEvent = new Event([
            'title' => 'حفل زفاف أحمد وسارة',
            'slug' => 'preview-event',
            'max_guests' => 500,
            'event_datetime' => Carbon::now()->addDays(14)->setHour(20)->setMinute(0),
            'location_name' => 'فندق الفورسيزونز، القاعة الملكية',
            'content_data' => $contentData,
        ]);
        
        // Temporarily bind relationships or required properties if templates use them directly
        $mockEvent->setRelation('template', $template);

        return view("themes.{$template->theme_identifier}.index", [
            'event' => $mockEvent,
            'isPublic' => true,
            'guest' => null, // Preview mode
        ]);
    }
}
