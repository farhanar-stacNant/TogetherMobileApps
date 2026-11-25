import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user_model.dart';
import '../models/event_model.dart';
import '../models/chat_model.dart';

class ApiService {
  // Automatically switches host: 127.0.0.1 for Web/Windows, 10.0.2.2 for Android Emulator
  static String get baseUrl {
    if (kIsWeb || defaultTargetPlatform == TargetPlatform.windows || defaultTargetPlatform == TargetPlatform.macOS) {
      return 'http://127.0.0.1:8000/api';
    }
    return 'http://10.0.2.2:8000/api';
  }

  static Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  static Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  static Future<void> clearToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  static Future<Map<String, String>> _headers() async {
    final token = await getToken();
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }

  // --- AUTH ---
  static Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    String? phone,
    required String password,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/register'),
      headers: await _headers(),
      body: jsonEncode({
        'name': name,
        'email': email,
        'phone': phone,
        'password': password,
      }),
    );
    final data = jsonDecode(response.body);
    if (response.statusCode == 201 && data['token'] != null) {
      await saveToken(data['token']);
    }
    return {'status': response.statusCode, 'data': data};
  }

  static Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: await _headers(),
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );
    final data = jsonDecode(response.body);
    if (response.statusCode == 200 && data['token'] != null) {
      await saveToken(data['token']);
    }
    return {'status': response.statusCode, 'data': data};
  }

  static Future<UserModel?> getCurrentUser() async {
    final response = await http.get(
      Uri.parse('$baseUrl/me'),
      headers: await _headers(),
    );
    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);
      return UserModel.fromJson(json['user']);
    }
    return null;
  }

  static Future<bool> updateProfile({
    String? name,
    int? age,
    String? city,
    String? avatarUrl,
    String? bio,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/profile'),
      headers: await _headers(),
      body: jsonEncode({
        'name': ?name,
        'age': ?age,
        'city': ?city,
        'avatar_url': ?avatarUrl,
        'bio': ?bio,
      }),
    );
    return response.statusCode == 200;
  }

  // --- HOME & USERS ---
  static Future<List<UserModel>> getNearbyUsers() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/users/nearby'),
        headers: await _headers(),
      );
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final list = data['users'] as List? ?? [];
        return list.map((u) => UserModel.fromJson(u)).toList();
      }
    } catch (_) {}
    return [];
  }

  static Future<List<UserModel>> searchUsers({String? query, String? city}) async {
    try {
      final uri = Uri.parse('$baseUrl/users').replace(queryParameters: {
        if (query != null && query.isNotEmpty) 'search': query,
        if (city != null && city.isNotEmpty) 'city': city,
      });
      final response = await http.get(uri, headers: await _headers());
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final list = (data['data'] ?? data['users'] ?? []) as List;
        return list.map((u) => UserModel.fromJson(u)).toList();
      }
    } catch (_) {}
    return [];
  }

  // --- EVENTS ---
  static Future<List<EventModel>> getUpcomingEvents() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/events/upcoming'),
        headers: await _headers(),
      );
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final list = data['events'] as List? ?? [];
        return list.map((e) => EventModel.fromJson(e)).toList();
      }
    } catch (_) {}
    return [];
  }

  static Future<List<EventModel>> getEvents({String? category, String? search}) async {
    try {
      final uri = Uri.parse('$baseUrl/events').replace(queryParameters: {
        if (category != null && category.isNotEmpty && category != 'all') 'category': category,
        if (search != null && search.isNotEmpty) 'search': search,
      });
      final response = await http.get(uri, headers: await _headers());
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final list = (data['data'] ?? []) as List;
        return list.map((e) => EventModel.fromJson(e)).toList();
      }
    } catch (_) {}
    return [];
  }

  static Future<EventModel?> getEventDetail(int id) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/events/$id'),
        headers: await _headers(),
      );
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        return EventModel.fromJson(data['event']);
      }
    } catch (_) {}
    return null;
  }

  static Future<Map<String, dynamic>> toggleJoinEvent(int id) async {
    final response = await http.post(
      Uri.parse('$baseUrl/events/$id/join'),
      headers: await _headers(),
    );
    return jsonDecode(response.body);
  }

  static Future<bool> createEvent({
    required String title,
    String? description,
    required String category,
    String? imageUrl,
    required String locationName,
    String? city,
    required DateTime startTime,
    int? maxParticipants,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/events'),
      headers: await _headers(),
      body: jsonEncode({
        'title': title,
        'description': description,
        'category': category,
        'image_url': imageUrl,
        'location_name': locationName,
        'city': city,
        'start_time': startTime.toIso8601String(),
        'max_participants': maxParticipants ?? 50,
      }),
    );
    return response.statusCode == 201;
  }

  // --- CHATS ---
  static Future<List<ConversationModel>> getConversations() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/conversations'),
        headers: await _headers(),
      );
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final list = data['conversations'] as List? ?? [];
        return list.map((c) => ConversationModel.fromJson(c)).toList();
      }
    } catch (_) {}
    return [];
  }

  static Future<List<MessageModel>> getMessages(int conversationId) async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/conversations/$conversationId/messages'),
        headers: await _headers(),
      );
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final list = (data['data'] ?? []) as List;
        return list.map((m) => MessageModel.fromJson(m)).toList();
      }
    } catch (_) {}
    return [];
  }

  static Future<MessageModel?> sendMessage(int conversationId, String text) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/conversations/$conversationId/messages'),
        headers: await _headers(),
        body: jsonEncode({'message': text}),
      );
      if (response.statusCode == 201) {
        final data = jsonDecode(response.body);
        return MessageModel.fromJson(data['message']);
      }
    } catch (_) {}
    return null;
  }
}
