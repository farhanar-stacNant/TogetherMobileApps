import 'user_model.dart';

class MessageModel {
  final int id;
  final int conversationId;
  final int senderId;
  final String message;
  final bool isRead;
  final DateTime createdAt;
  final UserModel? sender;

  MessageModel({
    required this.id,
    required this.conversationId,
    required this.senderId,
    required this.message,
    required this.isRead,
    required this.createdAt,
    this.sender,
  });

  factory MessageModel.fromJson(Map<String, dynamic> json) {
    return MessageModel(
      id: json['id'] is int ? json['id'] : int.parse(json['id'].toString()),
      conversationId: json['conversation_id'] is int
          ? json['conversation_id']
          : int.parse(json['conversation_id'].toString()),
      senderId: json['sender_id'] is int
          ? json['sender_id']
          : int.parse(json['sender_id'].toString()),
      message: json['message'] ?? '',
      isRead: json['is_read'] == 1 || json['is_read'] == true,
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at']) ?? DateTime.now()
          : DateTime.now(),
      sender: json['sender'] != null
          ? UserModel.fromJson(json['sender'] as Map<String, dynamic>)
          : null,
    );
  }
}

class ConversationModel {
  final int id;
  final String type; // 'direct', 'group'
  final String displayTitle;
  final String? displayAvatar;
  final int unreadCount;
  final DateTime? lastMessageAt;
  final MessageModel? lastMessage;
  final List<UserModel> participants;

  ConversationModel({
    required this.id,
    required this.type,
    required this.displayTitle,
    this.displayAvatar,
    this.unreadCount = 0,
    this.lastMessageAt,
    this.lastMessage,
    this.participants = const [],
  });

  factory ConversationModel.fromJson(Map<String, dynamic> json) {
    var rawParticipants = json['participants'] as List? ?? [];
    List<UserModel> parsedParticipants = rawParticipants
        .map((p) => UserModel.fromJson(p as Map<String, dynamic>))
        .toList();

    return ConversationModel(
      id: json['id'] is int ? json['id'] : int.parse(json['id'].toString()),
      type: json['type'] ?? 'direct',
      displayTitle: json['display_title'] ?? json['title'] ?? 'Chat',
      displayAvatar: json['display_avatar'] ?? json['avatar_url'],
      unreadCount: json['unread_count'] != null
          ? int.tryParse(json['unread_count'].toString()) ?? 0
          : 0,
      lastMessageAt: json['last_message_at'] != null
          ? DateTime.tryParse(json['last_message_at'])
          : null,
      lastMessage: json['last_message'] != null
          ? MessageModel.fromJson(json['last_message'] as Map<String, dynamic>)
          : null,
      participants: parsedParticipants,
    );
  }
}
