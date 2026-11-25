// Basic smoke test for Together App.
import 'package:flutter_test/flutter_test.dart';
import 'package:together_mobile/main.dart';

void main() {
  testWidgets('Together app renders splash screen smoke test', (WidgetTester tester) async {
    // Build TogetherApp and trigger a frame.
    await tester.pumpWidget(const TogetherApp());

    // Verify that the Together app branding renders.
    expect(find.text('together'), findsOneWidget);
    expect(find.text('REAL PEOPLE, REAL MOMENTS'), findsOneWidget);
  });
}
