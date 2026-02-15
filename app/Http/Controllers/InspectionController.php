public function storeWeb(Request $request)
{
    // اعتبارسنجی
    $validated = $request->validate([
        'inspection_date' => 'required|date',
        'daily_start_time' => 'required',
        'daily_end_time' => 'required',
        'contractor' => 'required|string',
        'contract_coefficient' => 'required|numeric',
    ]);

    // ذخیره در دیتابیس
    $inspection = Inspection::create([
        'inspection_date' => $request->inspection_date,
        'daily_start_time' => $request->daily_start_time,
        'daily_end_time' => $request->daily_end_time,
        'contractor' => $request->contractor,
        'contract_coefficient' => $request->contract_coefficient,
        'contract_number' => $request->contract_number,
        'whatsapp_number' => $request->whatsapp_number,
        'user_id' => auth()->id(),
        'status' => 'draft'
    ]);

    return redirect()->back()->with('success', 'بازدید با موفقیت ثبت شد');
}