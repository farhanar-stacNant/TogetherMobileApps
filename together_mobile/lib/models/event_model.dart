import 'user_model.dart';

class EventModel {
  final int id;
  final int creatorId;
  final String title;
  final String? description;
  final String category;
  final String? imageUrl;
  final String locationName;
  final String? city;
  final DateTime startTime;
  final int maxParticipants;
  final String status;
  final int participantsCount;
  final bool isJoined;
  final UserModel? creator;
  final List<UserModel> participants;

  EventModel({
    required this.id,
    required this.creatorId,
    required this.title,
    this.description,
    required this.category,
    this.imageUrl,
    required this.locationName,
    this.city,
    required this.startTime,
    required this.maxParticipants,
    required this.status,
    this.participantsCount = 0,
    this.isJoined = false,
    this.creator,
    this.participants = const [],
  });

  factory EventModel.fromJson(Map<String, dynamic> json) {
    var rawParticipants = json['participants'] as List? ?? [];
    List<UserModel> parsedParticipants = rawParticipants
        .map((p) => UserModel.fromJson(p as Map<String, dynamic>))
        .toList();

    return EventModel(
      id: json['id'] is int ? json['id'] : int.parse(json['id'].toString()),
      creatorId: json['creator_id'] is int
          ? json['creator_id']
          : int.parse(json['creator_id'].toString()),
      title: json['title'] ?? '',
      description: json['description'],
      category: json['category'] ?? 'hangout',
      imageUrl: json['image_url'],
      locationName: json['location_name'] ?? '',
      city: json['city'],
      startTime: json['start_time'] != null
          ? DateTime.tryParse(json['start_time']) ?? DateTime.now()
          : DateTime.now(),
      maxParticipants: json['max_participants'] != null
          ? int.tryParse(json['max_participants'].toString()) ?? 50
          : 50,
      status: json['status'] ?? 'upcoming',
      participantsCount: json['participants_count'] != null
          ? int.tryParse(json['participants_count'].toString()) ?? 0
          : parsedParticipants.length,
      isJoined: json['is_joined'] == true,
      creator: json['creator'] != null
          ? UserModel.fromJson(json['creator'] as Map<String, dynamic>)
          : null,
      participants: parsedParticipants,
    );
  }
}
